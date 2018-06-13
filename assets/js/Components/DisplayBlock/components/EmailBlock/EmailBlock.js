import React, { Component } from 'react';

import Email   from './components/Email/Email';
import Summary from './components/Summary/Summary';

class EmailBlock extends Component {

    render() {
        });

        return (
            <div className="mainBlock">
                <table className="emailList">
                    <colgroup>
                        <col span="3" className="select" />
                        <col className="emailName" />
                        <col className="emailTitle" />
                        <col className="whitespace" />
                        <col className="dateTag" />
                    </colgroup>
                    <tbody>
                        {emails}
                    </tbody>
                </table>
            </div>
        );
    }

}

export default EmailBlock;
