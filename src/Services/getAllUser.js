const getAllUser = (id) => {
    try {
        let user = fetch(`/public/getUser.php/api/alluser/${id}`)
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
