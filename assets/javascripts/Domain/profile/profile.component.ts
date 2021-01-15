import { inject, injectable } from "inversify";
import ProfileService from "./profile.service";

@injectable()
export default class ProfileComponent{
    private profileService:ProfileService;

    constructor(@inject(ProfileService) profileService:ProfileService){
        this.profileService=profileService;
    }

    googleAuthFormStat():void{
        $('#google_authentication_form_state').on('change',
            this.profileService.googleAuthFormStat
        );
    }
}