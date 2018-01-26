import React                 from 'react';
import ReactDOM              from 'react-dom';
import Jeemail                 from './Jeemail';
import registerServiceWorker from './registerServiceWorker';
import './Styles/stylesheets/screen.css';

ReactDOM.render(<Jeemail />, document.getElementById('root'));
registerServiceWorker();
