import React, { Component } from 'react';

class VerticalList extends Component {

    render() {
        const items = this.props.items.map((item) => {
            return <li key={item}>{item}</li>
        });

        return (
            <div className="verticalList">
                <ol>
                    {items}
                </ol>
            </div>
        );
    }

}

export default VerticalList;
