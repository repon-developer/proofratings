const Pages = (props) => {
    const hide_on = Array.isArray(props.hide_on) ? props.hide_on : [];

    const check_pages = (id) => {
        const index = hide_on.indexOf(id);
        if (index !== -1) {
            hide_on.splice(index, 1);
        } else {
            hide_on.push(id);
        }

        props.onUpdate({hide_on})
    }

    return (
        <table className="form-table">
            <caption>Page to show on</caption>
            <tbody>
                {proofratings.pages.map(page => (
                    <tr key={page.ID}>
                        <th scope="row">{page.post_title}</th>
                        <td>
                            <input defaultChecked={!hide_on.includes(page.ID)} onChange={() => check_pages(page.ID)} className="checkbox-switch" type="checkbox" />
                        </td>
                    </tr>
                ))}
                
            </tbody>
        </table>
    );
};

export default Pages;
