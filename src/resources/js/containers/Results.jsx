import React, { useState } from 'react';

import ResultContainer from "./ResultContainer.jsx";
import ResultDescription from "../components/ResultDescription.jsx";

export default function Results({ ip, webRtcIp, geolocation, cinscoreFlagged, fraudScore, vpn }) {
    const [children] = useState([
        <ResultDescription
            title={'IP Address'}
            content={`Your IP address is ${ip}. This means that sites you visit can see your location, internet
            provider, and more. ${!vpn ? 'You can hide this by using a VPN.' : ''}`}/>,
        <ResultDescription
            title={'WebRTC IP'}
            content={`WebRTC is a technology that allows streamlined communication between your browser and
            other computers. Your Web RTC IP is ${webRtcIp}.`}/>,
        <ResultDescription
            title={'VPN'}
            content={<p>
                A VPN acts as a middleman between your computer and the computer you're trying to connect to.
                This is just one way of masking your location online.

                <br/><br/>

                {vpn ? 'Great job! You\'re currently using a VPN.' :
                    'You are not using a VPN. Try using Mullvad VPN for the maximum privacy online.'}
        </p>}/>,
        <ResultDescription
            title={'Cinscore'}
            content={`${cinscoreFlagged ? 'Your IP is flagged as malicious by Cinscore.' :
                'Great news! Your IP isn\'t flagged by Cinscore!'}`}/>,
        <ResultDescription
            title={'Geolocation'}
            content={<div>
                Your IP address can give a lot of information about you. Here's what can be seen about yours:

                <ul className='mt-4 list-disc list-inside'>
                    <li>
                        You are in {geolocation['city']}, {geolocation['region']} in {geolocation['country_name']}
                    </li>
                    <li>
                        You use {geolocation['org']} for your internet
                    </li>
                    <li>
                        Your postal code is near {geolocation['postal']}
                    </li>
                </ul>
            </div>}/>,
        <ResultDescription
            title={'Fraud Score'}
            content={<div>
                A fraud score is a rating of whether an IP address could be malicious or not.

                <br/><br/>

                Having a high fraud score will make it more difficult for you to use the internet,
                because higher fraud scores require more frequent human verification.

                <br/><br/>

                Your fraud score is {fraudScore}. A score under 25 is considered good.

                <div className='mt-8 justify-center flex'>
                    <div className={`c100 p${fraudScore}`}>
                        <div className="slice">
                            <div className="bar"></div>
                            <div className="fill"></div>
                        </div>
                    </div>
                </div>
            </div>}/>
    ])

    return (
        <ul className='md:grid md:grid-cols-3 lg:grid-cols-3'>
            {children.map(child => {
                return <li key={child.props.title}><ResultContainer child={child}/></li>
            })}
        </ul>
    );
}
