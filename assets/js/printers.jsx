import React from 'react'
import axios from "axios";
import { useEffect, useState } from "react";


export default () => {



    initialAppState.map( object => object.data = 'No data');

    // Never ever ever ever ever remove stringify and parse, or you will spend 8 hours looking at JSON that somehow isn't JSON
    // Without these nothing works, too bad!
    const init = JSON.stringify(initialAppState);
    const state = JSON.parse(init);



// This function only works!!!
    const [printerdata, setPrinterdata] = useState(state);

    const  getPrinter = () => {
        return (printerdata.map ( async (printer) =>
        //map return array of promises, solve outside of map
        {
            const response =  await axios({
                method: "GET",
                url: `https://${printer.IP}/api/printer`,
                headers: {
                    "X-Api-Key": `${printer.APIkey}`,
                }
            });

            const data = response.data;
            return {...printer, data }  }))};

    // This function gets all the data from the printers supplied from the database and gets all actual data from each of them
    // All printers must return data or this function won't work
    const getAllPrinterData = () => Promise.all(getPrinter()).then(res=> setPrinterdata(res));

    console.log(printerdata);
    useEffect(() => {
        getAllPrinterData();
    }, []);


    return (
        <>
            {printerdata.map( printer =>


            <div key={printer.id}>
                <div className="col mt-5 ml-5">
                    <div className="card">
                        <div className="card-header bg-primary text-light">
                            <h4 className="card-title"> {printer.name} </h4>
                        </div>
                        <img
                            className="card-image-top ml-auto mr-auto border border-primary rounded"
                            src={'http://' + printer.IP + ':8080/?action=stream'}
                            // printer.ip :8080 ?action=stream
                            alt='Webcam disabled'
                            width="250"
                        />
                        <div className='card-body'>


                            {printer.data.state ?
                                <ul className="list-unstyled">
                                    <li><strong> {printer.data.state.text} </strong></li>
                                    <li> Bed <strong>{printer.data.temperature.bed.actual}</strong></li>
                                    <li> Tool<strong>{printer.data.temperature.tool0.actual}</strong></li>

                                </ul>

                                : ''}


                        </div>
                    </div>
                </div>

            </div>

                )}
        </>
    )}


/*
banned to the darkzone







 */