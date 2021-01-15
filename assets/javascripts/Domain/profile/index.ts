import container from "../../Container";
import ProfileComponent from "./profile.component";

let profileComponent:ProfileComponent
profileComponent = container.resolve<ProfileComponent>(ProfileComponent);
profileComponent.googleAuthFormStat();