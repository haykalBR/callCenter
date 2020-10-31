import ProfileEvent from './javascripts/Event/ProfileEvent';
import Default from './javascripts/Event/Default';
import UsersEvent from "./javascripts/Event/UsersEvent";
new Default();
new UsersEvent();
let profileEvent = new ProfileEvent();
profileEvent.googleAuthFormStat();
