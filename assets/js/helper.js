import axios from 'axios';



document.querySelectorAll('.formsubmit').forEach( item =>
    item.addEventListener( 'submit' ,
        (e) => {
            e.preventDefault();

            const form = e.target;
            const file_name = form.querySelector('#file_name').value;
            const printer_id = form.querySelector('#printer_id').value;

            sendFilesToPrinter(printer_id,file_name);
            // send to other fucntion to deal with sending file
        }
    ));

const sendFilesToPrinter = (printerid, filename) => {

    const printerIP = printerid.split(' ')[2];
    const printerAPI = printerid.split(' ')[4];

    console.log(printerIP,filename, printerAPI);
    const body = ({
            "command": "select",
            "print": true
        });

    axios({
        method: 'post',
        url: `http://${printerIP}/api/files/local/${filename}.gcode`,
        headers: {
            'Content-Type' : 'application/json',
            "X-Api-Key": printerAPI,
        },
        data: body
    })
        // if response is 204 return
        //else send file to printer then send printcommand
        .then(
            res => {
                console.log(res);
                if ( res.header === 200 ){ return }

                else {
                    fileUpload(filename)
                    //then this
                }


            });
};

const fileUpload = (filename) => {

// get file from server , then move to local pi
    const url = `http://wdev.be/wdev_arno/eindwerk/public/uploads/${filename}`;
    const formData = new FormData();
    formData.append('file',filename);
    const config = {
        headers: {
            'content-type': 'multipart/form-data'
        }
    }
};