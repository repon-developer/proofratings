const { useEffect } = React;

const Pages = (props) => {
    const on_pages = (Array.isArray(props?.on_pages) ? props.on_pages : []).map(item => parseInt(item));

    useEffect(() => {
        if ( Array.isArray(props?.on_pages) ) {
            return;
        }

        const on_pages = proofratings.pages.map(page => page.ID);
        props.onUpdate({on_pages })
    }, [])

    const check_pages = (id) => {
        const index = on_pages.indexOf(id);
        if (index !== -1) {
            on_pages.splice(index, 1);
        } else {
            on_pages.push(id);
        }

        props.onUpdate({on_pages})
    }

    return (
        <table className="form-table">
            <tbody>
                {proofratings.pages.map(page => (
                    <tr key={page.ID}>
                        <th scope="row">{page.post_title}</th>
                        <td>
                            <input defaultChecked={on_pages.includes(page.ID)} onChange={() => check_pages(page.ID)} className="checkbox-switch" type="checkbox" />
                        </td>
                    </tr>
                ))}
                
            </tbody>
        </table>
    );
};

export default Pages;
