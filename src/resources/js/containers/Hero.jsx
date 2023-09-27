import React from 'react';

export default function Hero() {
    return (
        <div className='background-main'>
            <div className='flex min-h-[100vh]'>
                <div className='mx-auto'>
                    <div className='mx-auto mt-[10%] text-gray-800 font-extrabold font-monserrat text-6xl text-center'>
                        Find out what your IP says.

                        <h1 className='text-blue-500'>Completely free.</h1>

                        <button className='mt-[2%] text-lg bg-blue-700 rounded-xl px-[5%] py-[2%] text-white shadow-2xl'>
                            <a href='#begin'>
                                Get Started
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    )
}
