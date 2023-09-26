import React, { useState } from 'react';

import { motion } from 'framer-motion';

export default function ResultContainer(props) {
    const [open, setOpen] = useState(false);

    const animationData = {
        height: open ? 'fit-content' : '0px',
        marginBottom: open ? '24px' : '0px'
    };

    return (
        <motion.div animate={open ? 'open' : 'closed'} className='pt-2 pb-5'>
            <button onClick={() => setOpen(!open)} className='items-center justify-between text-white'>
                <motion.div animate={open ? 'open' : 'closed'}>
                    <span className={'text-center text-xl font-medium'}>
                        <span className='text-green-400'>
                            {props.child.props.title}
                        </span>

                        <span className='block text-sm font-medium text-gray-600'>
                            Click to learn more
                        </span>
                    </span>
                    <motion.div initial={false} animate={animationData} className='overflow-hidden'>
                        <div className='text-md mx-3'>{props.child}</div>
                    </motion.div>
                </motion.div>
            </button>
        </motion.div>
    )
}
