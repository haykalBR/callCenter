import ProfileEvent from './javascripts/Event/ProfileEvent';
import Default from './javascripts/Event/Default';
import UsersEvent from "./javascripts/Event/UsersEvent";
new Default();
let usersEvent= new UsersEvent();
usersEvent.generatePassword();
usersEvent.deleteUser();
usersEvent.passwordUser();
let profileEvent = new ProfileEvent();
profileEvent.googleAuthFormStat();
import 'select2/dist/js/select2';
import 'select2/dist/css/select2.css';

const url :any = new URL('http://www.mercure.local.com:8001/.well-known/mercure');
url.searchParams.append('topic', '/test');
const eventSource = new EventSource(url, { withCredentials: true });
eventSource.onmessage = e => {
    console.log('Nouveau message');

}
