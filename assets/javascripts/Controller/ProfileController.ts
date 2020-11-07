import axios from '../Config/axios';
import toastr from '../Config/toastr';

export default class ProfileController{

    googleAuthFormStat(event:JQuery.EventBase,url:string, token: string | number | string[]){
        axios({
            method: 'post',
            url: url,
            data: {
                state: event.target.checked,
                _token: token,
            }
         }).then((response) => {
            toastr.info(response.data)
        }, (error) => {

         });
    }
}
