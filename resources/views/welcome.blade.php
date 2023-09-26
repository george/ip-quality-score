<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IP Quality Score</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400" rel="stylesheet" />

    @vite('resources/css/app.css')
</head>
<body class="antialiased">
<div class="overflow-none min-h-screen selection:bg-red-500 selection:text-white">
    <nav class='p-8 bg-[#121212] text-center text-white text-lg text-green-500 font-semibold'>
        Your IP is <span class='text-red-500'>{{$ip}}</span>. Are you vulnerable?
    </nav>

    <div class='flex h-[75vh]'>
        <div class='m-auto text-white text-center'>
            <div class='w-screen-full py-[2%] text-green-400'>
                <div id='loading' class="py-[5%] w-screen-full py-[10%] border-green-800 bg-[#121212] w-[500px] border-4 rounded-lg">
                    <div class="py-[10%] font-semibold">
                        Loading your IP information...

                        <br/>
                        <br/>

                        This should just take a second.
                    </div>
                </div>
            </div>

            <div id='results' class='justify-center text-center pt-[15%] flex hidden bg-[#151515] w-[75vw] h-[65vh]'>
                <div class="md:grid md:grid-cols-3 lg:grid-cols-3">
                    
                </div>
                <div>
                    <h1>
                        Fraud score: <span id='fraud-score-value'>100</span>
                    </h1>

                    <div class='mt-8'>
                        <div id='fraud-score' class='c100'>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script defer>
                /*
                Taken from Martin Devillers on StackOverflow
                 */

                window.RTCPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;   //compatibility for firefox and chrome
                const pc = new RTCPeerConnection({iceServers:[]}), noop = function(){};

                pc.createDataChannel("");
                pc.createOffer(pc.setLocalDescription.bind(pc), noop);

                let localRtcIp;

                pc.onicecandidate = function(ice){
                    if(!ice || !ice.candidate || !ice.candidate.candidate) {
                        return;
                    }

                    try {
                        localRtcIp = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)[1];
                    } catch (exc) {}

                    pc.onicecandidate = noop;
                };

                // fetch('/api/check').then((response) => {
                //     response.json().then((data) => {
                const data = JSON.parse('{"ip":"12.19.29.29","geolocationInfo":{"ip":"12.19.29.29","network":"12.19.28.0\/23","version":"IPv4","city":"Hingham","region":"Massachusetts","region_code":"MA","country":"US","country_name":"United States","country_code":"US","country_code_iso3":"USA","country_capital":"Washington","country_tld":".us","continent_code":"NA","in_eu":false,"postal":"02043","latitude":42.2229,"longitude":-70.8819,"timezone":"America\/New_York","utc_offset":"-0400","country_calling_code":"+1","currency":"USD","currency_name":"Dollar","languages":"en-US,es-US,haw,fr","country_area":9629091,"country_population":327167434,"asn":"AS7018","org":"ATT-INTERNET4"},"cinscoreFlagged":false,"fraudScore":17,"vpn":false}');

                const fraudScore = data['fraudScore'];

                // console.log(data);

                document.getElementById('fraud-score').className += ' p' + fraudScore;
                document.getElementById('fraud-score-value').innerText = fraudScore;

                document.getElementById('loading').className += ' hidden';
                document.getElementById('results').className =
                    document.getElementById('results').className.replace('hidden', '');
                // });
                // })
            </script>
        </div>
    </div>
</div>
</body>
</html>
