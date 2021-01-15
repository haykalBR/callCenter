import container from '../../Container';
import UserComponent from "./user.component";

let users:UserComponent;
users=container.resolve<UserComponent>(UserComponent);
users.generatePassword();
users.deleteUser();
users.passwordUser();
users.reloadPermissions();
users.getRoles();