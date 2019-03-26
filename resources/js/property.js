class Property {
    constructor(property) {
        this.wherehas = null;
        switch (property) {
            case 'user':
                this.path = '/properties/hrs/users/';
                break;
            case 'customer':
                this.path = '/properties/hrs/users/';
                break;
            case 'vehicle':
                this.path = '/properties/hrs/users/';
                break;
            case 'route':
                this.path = '/properties/hrs/users/';
                break;
            case 'plan':
                this.path = '/properties/hrs/users/';
                break;
        }
    }

    whereHas(relation){
        this.wherehas = relation;

        return this;
    }

    getIdentity(groupBy) {
        this.path += 'getIdentity';

        return new Promise((resolve, reject) => {
            axios.get(this.path, {
                params: {
                    groupBy: groupBy,
                    whereHas: this.wherehas
                }})
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    console.log(error.response.data);
                    reject(error.response.data);
                });
        });
    }
}
