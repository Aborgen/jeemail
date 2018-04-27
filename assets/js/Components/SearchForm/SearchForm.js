import React, { Component } from 'react';

// Components
import Button from '../../Components/Button/Button';

class SearchForm extends Component {

    render() {
        return (
            <div className="searchForm">
                <form method="get">
                    <input type="text" label="search"></input>
                    <Button type={"submit"} name={"submit"} text={"❓"} />
                </form>

            </div>
        );
    }

}

export default SearchForm;
