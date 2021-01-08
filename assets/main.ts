import ProfileEvent from './javascripts/Event/ProfileEvent';
import Default from './javascripts/Event/Default';
import UsersEvent from "./javascripts/Event/UsersEvent";
import './javascripts/Element/index';
import container from './Container';
import LocalizationEvent from './javascripts/Event/LocalizationEvent';


container.resolve<LocalizationEvent>(LocalizationEvent);

new Default();
new UsersEvent();

let profileEvent = new ProfileEvent();
profileEvent.googleAuthFormStat();
import 'select2/dist/js/select2';
import 'select2/dist/css/select2.css';


