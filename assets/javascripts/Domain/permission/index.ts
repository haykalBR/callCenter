import container from '../../Container';
import PermissionComponent from './permission.component';

let permisson:PermissionComponent;
permisson=container.resolve<PermissionComponent>(PermissionComponent);
permisson.refresh();
permisson.addNewPerlission();