import React, { useRef } from 'react';

import { motion } from 'framer-motion';
import { useIsVisible } from '../hooks/useIsVisible';

import SearchWindow from '../containers/SearchWindow';

export default function GetStarted() {
    const ref = useRef();
    const visible = useIsVisible(ref);

    return (
        <div className='background-flip pt-[50%]'>
            <div className='bg-white'>
                <motion.div ref={ref} animate={visible ? { x: '5%' } : 0 } transition={{ transition: 'ease-in', duration: .75 }} className='pt-2 pb-5'>
                    <div className='px-[10%] my-[-10%]'>
                        <div ref={ref} className='bg-white shadow-2xl shadow-slate-750 z-20 rounded-2xl w-[90%] py-[10%]'>
                            <SearchWindow/>
                        </div>
                    </div>
                </motion.div>

                <div id='begin'/>
            </div>
        </div>
    )
}
