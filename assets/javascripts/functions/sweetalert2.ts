import Swal from 'sweetalert2'
import axios from "../Config/axios";
import {randomString} from "./Strings";

export function deleterecord( email:string  , route:string ) {
    Swal.fire({
        title: 'Are you sure?'+email,
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            axios({
                method: 'DELETE',
                url: route,
            }).then((response) => {
                Swal.fire(response.data);
            }, (error) => {
                Swal.fire(error.data);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    })
}
export function userpassword( email:string , route:string) {
    Swal.fire({
        title: 'Are you sure?'+email,
        text: "You won't be able to genereate new password  this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, genreate password it!',
        showLoaderOnConfirm: true,
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
        },
        allowOutsideClick: () => !Swal.isLoading()
    })
}