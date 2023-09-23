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

                    <script>
                        fetch('/api/check').then((response) => {
                            response.json().then((data) => {
                                const fraudScore = data['fraudScore'];

                                console.log(fraudScore);

                                document.getElementById('fraud-score').className += ' p' + fraudScore;
                                document.getElementById('loading').className += ' hidden';
                                document.getElementById('results').className =
                                    document.getElementById('results').className.replace('hidden', '');
                            });
                        })
                    </script>
                </div>
            </div>

            <div id='results' class='hidden grid bg-[#151515] w-[75vw] h-[65vh]'>
                Fraud score:
                <div id='fraud-score' class='c100 center'>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
