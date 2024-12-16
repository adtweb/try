import Alpine from "alpinejs";
import axios from "axios";

Alpine.store("user", {
    user: null,

    init() {
        axios
            .get(route("api.current-user"))
            .then((response) => {
                this.user = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    },
});
