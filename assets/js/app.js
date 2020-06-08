
import ReactDOM from 'react-dom';
import React from 'react'
import ReactApp from './ReactApp'

console.log('hello App');
ReactDOM.render(
    <React.StrictMode>
        <ReactApp />
    </React.StrictMode>,
    document.getElementById('root')
);