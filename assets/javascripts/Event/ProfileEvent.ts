const $ = require('jquery');
import ProfileController  from '../Controller/ProfileController';

export default class ProfileEvent{
    private profileController:ProfileController ;
    googleAuthFormStat(){
        $('#google_authentication_form_state').on('change',  (event) => {
            event.preventDefault();
            //@ts-ignore
            let url:string = $(this).closest('form')[0].action;
            this.profileController.googleAuthFormStat(
                event, 
                url, 
                $('#google_authentication_form__token').val()
            );
        });
    }

}
