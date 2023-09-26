import React, { useState } from 'react';
import { useEffectOnce} from 'usehooks-ts';
import Results from "./Results.jsx";

/*
Taken from Martin Devillers on StackOverflow
 */
const fetchWebRtcLocalIp = (updateResults) => {
    window.RTCPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
    const pc = new RTCPeerConnection({iceServers:[]}), noop = function(){};

    pc.createDataChannel("");
    pc.createOffer(pc.setLocalDescription.bind(pc), noop);

    pc.onicecandidate = function(ice){
        if(!ice || !ice.candidate || !ice.candidate.candidate) {
            return;
        }

        try {
            updateResults(/([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)[1]);
        } catch (exc) {
        }

        pc.onicecandidate = noop;
    };
}
export default function Hero() {
    const [results, setResults] = useState(null);
    const [webRtcIp, setWebRtcIp] = useState('Unknown (local)');

    const updateResults = (webRtcIp) => {
        if (results) {
            const newResults = results;
            newResults.props.webRtcIp = webRtcIp;

            setResults(newResults);
        }

        setWebRtcIp(webRtcIp);
    }

    fetchWebRtcLocalIp(updateResults);

    useEffectOnce(() => {
        fetch('/api/check').then((response) => {
            response.json().then((data) => {
                setResults(<Results
                        ip={data['ip']}
                        webRtcIp={webRtcIp}
                        geolocation={data['geolocationInfo']}
                        cinscoreFlagged={data['cinscoreFlagged']}
                        fraudScore={data['fraudScore']}
                        vpn={data['vpn']}
                    />
                );
            })
        });
    });

    return (
        <div className='m-auto basis-[75%]'>
            <div className="py-[10%] text-center text-green-400 border-green-800 bg-[#121212] border-4 rounded-lg">
                {results ||
                    <div className="font-semibold">
                        Loading your IP information...

                        <br/>
                        <br/>

                        This should just take a second.
                    </div>
                }
            </div>
        </div>
    )
}
