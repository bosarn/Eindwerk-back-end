import React from 'react'
import axios from "axios";
import { useEffect, useState } from "react";


export default () => {



    initialAppState.map( object => object.data = 'No data');

    // Never ever ever ever ever remove stringify and parse, or you will spend 8 hours looking at JSON that somehow isn't JSON
    // Without these nothing works, too bad!
    const init = JSON.stringify(initialAppState);
    const state = JSON.parse(init);


    const [Printerdata, setPrinterdata] = useState(state);

    // Even though it looks like this function doesn't do anything it is VITAL for the inner workings of the next function
    // By a combination of sheer willpower and magic it can set the data of a different useState const.
    const [Printer, setPrinter] = useState([]);


// This function only works by sheer luck, do not sneeze on it or it will collapse!
    const  printergetter  =  () =>
        Printerdata.map( (printer,i) =>
            axios({
                method: "GET",
                url: `http://${printer.IP}/api/printer`,
                headers: {
                    "X-Api-Key": `${printer.APIkey}`,
                }
            }).then( res => {

                let copy = Printerdata;
                // data of results must be in array because React can't handle children
                copy[i].data = [res.data];
                setPrinter(printer);
                setPrinterdata(copy);
            }));


    useEffect(() => {
        printergetter();
    }, []);


    return (
        <>
            {Printerdata ?

                Printerdata.map(printer =>
                    (

                        <div key={printer.id}>
                            <div className="col mt-5 ml-5">
                                <div className="card">
                                    <img
                                        className="card-image-top"
                                        src={ 'http://'+printer.IP+':8080/?action=stream'}
                                        // printer.ip :8080 ?action=stream
                                        alt='Webcam disabled'
                                        width="250"
                                    />
                                    <div className='card-body'>
                                        <h4 className="card-title"> {printer.name}</h4>

                                        {printer.data[0].state ?
                                            <ul class="list-unstyled">
                                                <li>  <strong> {printer.data[0].state.text} </strong></li>
                                                <li> Bed <strong>{printer.data[0].temperature.bed.actual}</strong></li>
                                                <li> Tool<strong>{printer.data[0].temperature.tool0.actual}</strong></li>

                                            </ul>

                                            : ''}


                                    </div>
                                </div>
                            </div>

                        </div>

                    )
                )
                : ( "unavailable")}

        </>
    )}


