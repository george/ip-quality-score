import React, { useState } from 'react';
import { useEffectOnce} from 'usehooks-ts';
import { PuffLoader } from 'react-spinners';
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
            const ip = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)[1];

            updateResults(ip);
        } catch (exc) {
            updateResults('Unknown');
        }

        pc.onicecandidate = noop;
    };
}

export default function SearchWindow() {
    const [results, setResults] = useState(null);
    const [webRtcIp, setWebRtcIp] = useState(null);

    useEffectOnce(() => {
        fetchWebRtcLocalIp(setWebRtcIp);
    });

    useEffectOnce(() => {
        fetch('/api/check').then((response) => {
            response.json().then((data) => {
                setResults(data);
            })
        });
    });

    return (
        <div className='flex flex-col basis-[100%]'>
            <span className='mx-auto text-center'>
                {results && webRtcIp ? <Results
                    ip={results['ip']}
                    webRtcIp={webRtcIp}
                    geolocation={results['geolocationInfo']}
                    cinscoreFlagged={results['cinscoreFlagged']}
                    fraudScore={results['fraudScore']}
                    vpn={results['vpn']}
                /> : <PuffLoader size='100px' color='#70d7ff' loading={true}/>}
            </span>
        </div>
    )
}
