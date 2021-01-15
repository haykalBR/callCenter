import { Container } from "inversify";
import ProfileService from "./Domain/profile/profile.service";
import UsersService from "./Domain/user/user.service";
import PermissionService from "./Domain/permission/permission.service";
import LocalizationService from "./Domain/localization/localization.service";
import MercureService from "./Shared/component/mercure/mercure.service";
import DatatableFactory from "./Shared/factory/datatableFactory";
import MainService from "./Shared/component/main/main.service";

let container= new Container()
container.bind<UsersService>(UsersService).toSelf();
container.bind<DatatableFactory>(DatatableFactory).toSelf();
container.bind<ProfileService>(ProfileService).toSelf();
container.bind<PermissionService>(PermissionService).toSelf();
container.bind<LocalizationService>(LocalizationService).toSelf();
container.bind<MainService>(MainService).toSelf();
container.bind<MercureService>(MercureService).toSelf();

export default container;