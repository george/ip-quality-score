import React from 'react';
import ReactDOM from 'react-dom';

import Hero from './containers/Hero.jsx';

export default function App() {
    return (
        <div className='flex h-[75vh]'>
            <Hero/>
        </div>
    );
}

if (document.getElementById('root')) {
    /*
    Using ReactDOM.render from react-dom instead of using ReactDOM.createRoot from
    react-dom/client is not supported, but I was having issues with createRoot not
    using my root element, so I've had to use an older version.
     */
    ReactDOM.render(<App />, document.getElementById('root'));
}
