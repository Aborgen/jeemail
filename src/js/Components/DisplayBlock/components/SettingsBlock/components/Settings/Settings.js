import React, { Component } from 'react';

class Settings extends Component {

    componentDidMount() {
        return;
    }

    mapType(type) {
        if(type === 'radio') {
            return this.mapRadio;
        }

        return this.mapSelection;
    }

    mapRadio(rows, checked) {
        const radioRows = rows.map((row, i) => {
            const dataChunks = row['data'].map((data, n) => {
                if(n === checked[i]) {
                    return (
                        <div key={`${row.id}${n}`}>
                            <input type="radio"
                                   id={`${row.id}${n}`}
                                   name={`${row.id}`} defaultChecked />
                            <label htmlFor={`${row.id}${n}`}>{data}</label>
                        </div>
                    );
                }
                return (
                    <div key={`${row.id}${n}`}>
                        <input type="radio" id={`${row.id}${n}`} name={`${row.id}`} />
                        <label htmlFor={`${row.id}${n}`}>{data}</label>
                    </div>
                );
            });
            return (
                <tr key={row.id}>
                    <td><div>{row.name}</div></td>
                    <td className="radioPair">
                        {dataChunks}
                    </td>
                </tr>
            )
        });

        return radioRows;
    }

    mapSelection(rows, checked) {
        const selectionRows = rows.map((row) => {
            const dataChunks = row['data'].map((data, i) => {
                const options = data.enum.map((num, n) => {
                    return (
                        <option key={`${row.id}${num}`} value={num}>
                            {num}
                        </option>
                    );
                });
                return (
                    <div key={`${row.id}${i}`}>
                        {data.before}
                        <select id={`${row.id}${i}`} defaultValue={checked[i]}>
                            {options}
                        </select>
                        {data.after}
                    </div>
                );
            });
            return (
                <tr key={row.id}>
                    <td>
                        <div>{row.name}</div>
                    </td>
                    <td className="selectPair">
                        {dataChunks}
                    </td>
                </tr>
            )
        });

        return selectionRows;
    }

    render() {
        const { type, settings, checked } = this.props;
        const rows = this.mapType(type)(settings, checked);
        const Fragment = React.Fragment;
        return (
            <Fragment>{rows}</Fragment>
        );
    }
}

export default Settings;
