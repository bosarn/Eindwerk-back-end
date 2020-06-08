import React from "react";
import axios from "axios";
import { useEffect, useState } from "react";

export default () => {
    // for each printer in array of objects do get
    // render camera and data for each printer
    //initialstate = printerdata state changes the object with correct ip or id
  const [printerdata, setPrinterdata] = useState({});
console.log(initialAppState);
    console.log('qqqqqqqqq react');
  const printergetter = () =>
    axios({
      method: "get",
      url: "http://192.168.0.134/api/printer",
      headers: {
        "x-api-key": "ECB47339A16F42D0BA8588ED4DD3603F",
      },
    }).then((res) => {
      setPrinterdata(res.data.state);
      console.log(res.data);
    });

  useEffect(() => {
    printergetter();
  }, []);

  return (
      initialAppState.map( printer=>
    <div className="container mt-5">
      <div className="card">
        <img
          className="card-image-top"
          src="http://192.168.0.134:8080/?action=stream"
          // printer.ip :8080 ?action=stream
          alt='Webcam disabled'
          width="400"
        />

          <h4 className="card-title"> {printer.name} </h4>
        <div className="card-body">
          printerstatus = {printerdata.text}
          Location = {printer.location}

        </div>
      </div>
    </div>
  ))
};
//        Printing? {printerdata.flags.printing ? <p>bingo</p> : ''}
