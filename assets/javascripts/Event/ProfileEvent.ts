import * as $ from 'jquery';
import ProfileController  from '../Controller/ProfileController';

export default class ProfileEvent{
    profileController:ProfileController ;
    googleAuthFormStat(){
        $('#google_authentication_form_state').on('change', function (event) {
            event.preventDefault();
            const url = $(this).closest('form')[0].action;
            this.profileController.googleAuthFormStat(
                event, 
                url, 
                $('#google_authentication_form__token').val()
            );
        });
    }
}
