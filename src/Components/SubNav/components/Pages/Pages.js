import React, { Component } from 'react';

class Pages extends Component {

    render() {
        return (
            <div className="pages">
                <div className="pageSort">
                    <span>
                        <span>1</span>
                        --
                        <span>100</span>
                        of
                        <span>100</span>
                    </span>
                </div>
                <div className="pagiation">
                    <span>&#60;</span>
                </div>
                <div>
                    <span>&#62;</span>
                </div>
            </div>
        );
    }

}

export default Pages;
