import React from "react";
import axios from "axios";
import { useEffect, useState } from "react";
//import {ArgumentAxis, ValueAxis,  Chart,  LineSeries, from '@devexpress/dx-react-chart-material-ui';





export default () => {



    const [printerdata, setPrinterdata] = useState('');

    const printergetter = () =>
        axios({
            method: "GET",
            url: "http://192.168.0.134/api/printer",
            headers: {
                "X-Api-Key": "ECB47339A16F42D0BA8588ED4DD3603F",
            },
        }).then((res) => {
            setPrinterdata(res.data);
        });

    useEffect(() => {
        printergetter();
    }, []);



    return (
        <>
            <div className="row">
                <div className="col mt-5">
                    <div className="card">
                        <img
                            className="card-image-top"
                            src="https://192.168.0.134:8080/?action=stream"
                            // printer.ip :8080 ?action=stream
                            alt='Webcam disabled'
                            width="400"
                        />
                        <div className='card-body'>
                        {printerdata ? (
                            <ul>
                                <li>
                                    Printer up: <strong>{printerdata.state.text}</strong>
                                </li>
                                <li>
                                    SD card ready:
                                    <strong>{printerdata.sd.ready ? "ready" : "not ready"}</strong>
                                </li>
                                <li>
                                    Bed temperature:
                                    <strong>{printerdata.temperature.bed.actual}</strong>
                                </li>
                                <li>
                                    Hotend temp:
                                    <strong>{printerdata.temperature.tool0.actual}</strong>
                                </li>
                            </ul>
                        ) : (
                            "printerdata is currently unavailabe"
                        )}
                        </div>
                    </div>
                </div>

            </div>


        </>
    );

};
