import { injectable, inject } from "inversify";
import "reflect-metadata";

@injectable()
export default class LocalizationController{
    datatable() {
        console.log("localization controller : datatable");
        return true;
    }
}