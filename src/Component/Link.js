const Link = (props) => {
    const update_link = (name, value) => props.onUpdate(name, value);

    return (
        <React.Fragment>
            <tr>
                <th scope="row">Link</th>
                <td>
                    <input
                        type="checkbox"
                        checked={props?.enable}
                        className="checkbox-switch checkbox-yesno"
                        onChange={() => update_link('enable', !props?.enable)}
                    />
                </td>
            </tr>

            {props?.enable && (
                <>
                    <tr>
                        <th scope="row">URL</th>
                        <td>
                            <input type="text" value={props?.url} onChange={(e) => update_link('url', e.target.value)} />

                            <label style={{marginLeft: 15}}>
                                <input
                                    type="checkbox"
                                    className="checkbox-switch checkbox-yesno"
                                    defaultChecked={props?._blank}
                                    onChange={() => update_link('_blank', !props?._blank)}
                                /> Open in blank
                            </label>
                        </td>
                    </tr>
                </>
            )}
        </React.Fragment>
    );
};

export default Link