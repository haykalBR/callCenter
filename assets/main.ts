import ProfileEvent from './javascripts/Event/ProfileEvent';
import Default from './javascripts/Event/Default';
import UsersEvent from "./javascripts/Event/UsersEvent";
import PermissonEvent from "./javascripts/Event/PermissonEvent";

new Default();
let usersEvent= new UsersEvent();
let permissonEvent= new PermissonEvent();
usersEvent.generatePassword();
usersEvent.deleteUser();
usersEvent.passwordUser();
usersEvent.reloadPermissions();
usersEvent.permissionsGrantAndRevoke();
usersEvent.getRoles();
let profileEvent = new ProfileEvent();
profileEvent.googleAuthFormStat();
permissonEvent.refresh();
permissonEvent.addNewPerlission();
import 'select2';
//console.warn(checkPermection('admin_profile_edit'));

/*const url :any = new URL('http://www.mercure.local.com:8001/.well-known/mercure');
url.searchParams.append('topic', '/test');
const eventSource = new EventSource(url, { withCredentials: true });
eventSource.onmessage = e => {
    console.log('Nouveau message');
}*/
/*$(() => {
    $('#permission_guardName').select2({
        width: 'resolve',
        tags: true
    });
    $('#user_accessRoles').select2({
        width: 'resolve',
    });
    $('#user_grantPermission').select2({
        width: 'resolve',
    });
    $('#user_revokePermission').select2({
        width: 'resolve',
    });

});*/
const url:any = new URL('http://www.mercure.local.com:8001/.well-known/mercure');
url.searchParams.append('topic', 'csv:123456');
const eventSource = new EventSource(url);
const messageElt :any = document.getElementById('message');
const progressElt :any = document.getElementById('progress');


eventSource.onmessage = e => {
    const payload = JSON.parse(e.data);

    if (payload.type === 'progress') {
        if (!payload.data.total) {
            return
        }
        let percentage = (payload.data.current / payload.data.total) * 100 ;
        console.log(percentage,payload.data.current,payload.data.tota);


        progressElt.style = `width: ${percentage}%`;
    } else if (payload.type === 'message') {
        messageElt.innerHTML = payload.data;

    }else if(payload.type ==="error"){
        messageElt.innerHTML = payload.data;
    }
};
import container from './Container';
import LocalizationEvent from './javascripts/Event/LocalizationEvent';
container.resolve<LocalizationEvent>(LocalizationEvent)
