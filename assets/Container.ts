import { Container } from "inversify";
import LocalizationController from "./javascripts/Controller/LocalizationController";

let container= new Container()
container.bind<LocalizationController>(LocalizationController).toSelf()

export default container