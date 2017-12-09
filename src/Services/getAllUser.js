const getAllUser = (id) => {
    try {
        let user = fetch(`/user?id=${id}`)
            .then((data) => {
                return data.json();
        });

        return user;
    }
    catch (err) {
        console.log(err);
    }
}

export default getAllUser;
