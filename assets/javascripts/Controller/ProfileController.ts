import axios from '../Config/axios';
import toastr from '../Config/toastr';

export default class ProfileController{

    public googleAuthFormStat(event,url:string, token:string){
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
