import container from './Container';
import MainComponent from './Shared/component/main/main.component';
import MercureComponent from './Shared/component/mercure/mercure.component';

container.resolve<MainComponent>(MainComponent);
container.resolve<MercureComponent>(MercureComponent);