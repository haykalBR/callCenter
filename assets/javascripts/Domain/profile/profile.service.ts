import { injectable } from 'inversify';
import axios from '../../Config/axios';
import toastr from '../../Config/toastr';
import "reflect-metadata";

@injectable()
export default class ProfileService{
    constructor(){}
    googleAuthFormStat(event:JQuery.EventBase):void{
        event.preventDefault();
        let url:string = "/admin/profile/googleAuthentication";
        axios({
            method: 'post',
            url: url,
            data: {
                state: event.target.checked,
                _token: $('#google_authentication_form__token').val(),
            }
         }).then((response) => {
            toastr.info(response.data)
        }, (error) => {

        });
    }
}