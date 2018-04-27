import React, { Component } from 'react';

// Components
import EmailCount           from './components/EmailCount/EmailCount';
import ProgressArrow        from './components/ProgressArrow/ProgressArrow';

class Pages extends Component {

    handleClick(e) {
        if (e.target.localName === 'p') {
            console.log(e.target.parentNode);
            return;
        }
        console.log(e.target);
    }

    render() {

        return (
            <div className="pages">
                <EmailCount />
                <div className="pagination">
                    <ProgressArrow className="progressArrow-prev" text={'\u3008'} click={this.handleClick.bind(this)} />
                    <ProgressArrow className="progressArrow-next" text={'\u3009'} click={this.handleClick.bind(this)} />
                </div>
            </div>
        );
    }
}

export default Pages;
