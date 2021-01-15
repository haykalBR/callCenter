import Swal from '../../Config/sweetAlert';
import axios from "../../Config/axios";
import {randomString} from "./strings";

export function deleterecord( email:string  , route:string ):void {
    Swal.fire({
        title: 'Are you sure?'+email,
        text: "You won't be able to revert this!",
        confirmButtonText: 'Yes, delete it!',
        preConfirm: () => {
            axios({
                method: 'DELETE',
                url: route,
            }).then((response) => {
                Swal.fire(response.data);
            }, (error) => {
                Swal.fire(error.data);
            });
        }
    })
}
export function userpassword( email:string , route:string):void {
    Swal.fire({
        title: 'Are you sure?'+email,
        text: "You won't be able to genereate new password  this!",
        confirmButtonText: 'Yes, genreate password it!',
        preConfirm: () => {
            axios({
                method: 'post',
                url: route,
                data: {
                    password: randomString(10,20)
                }
            }).then((response) => {
                Swal.fire(response.data);
            }, (error) => {
                Swal.fire(error.data);
            });
        }
    })
}