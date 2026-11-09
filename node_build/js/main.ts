import { GreetingButtonComponent } from './Greeting/GreetingButtonComponent';
import { UserInfoService } from './User/UserInfoService';

const greetingButtonComponent = new GreetingButtonComponent(new UserInfoService());

document.addEventListener('DOMContentLoaded', () => greetingButtonComponent.show());
