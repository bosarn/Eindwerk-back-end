import ReactDOM from 'react-dom';
import React from 'react'
import Printers from './printers'
import Orderform from './orderform'


ReactDOM.render(
    <React.StrictMode>
        <Printers/>
        <Orderform/>
    </React.StrictMode>,
    document.getElementById('root')
);