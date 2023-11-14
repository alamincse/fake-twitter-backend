<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: #e3f2fd;">
                    <div class="container-fluid">
                        <Link :href="route('dashboard')" class="text-decoration-none">
                            <h4>Fake Twitter</h4>
                        </Link>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 10px">

                            </ul>

                            <h6 style="margin-right: 4px">Welcome {{ userInfo.name }}</h6>
                            <button @click="logout" class="btn btn-sm btn-primary">Logout</button>
                        </div>
                    </div>
                </nav>

                <div class="container" style="border: 1px solid #ccc;border-radius: 4px;">
                    <div class="row mt-4">
                        <div class="col-md-3 mb-4">
                            <div class="card" style="background: #f0f0f0">
                                <div class="card-body">
                                    <h5 class="card-title">Total Admins</h5>
                                    <p class="card-text">
                                        <strong>{{ totalInfo.totalAdmin }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card" style="background: #f0f0f0">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text">
                                        <strong>{{ totalInfo.totalUser }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card" style="background: #f0f0f0">
                                <div class="card-body">
                                    <h5 class="card-title">Total Tweets</h5>
                                    <p class="card-text">
                                        <strong>{{ totalInfo.totalTweet }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card" style="background: #f0f0f0">
                                <div class="card-body">
                                    <h5 class="card-title">Total Likes</h5>
                                    <p class="card-text">
                                        <strong>{{ totalInfo.totalLike }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5>Register User List</h5>
                                <table class="table table-striped  table-hover">
                                    <thead>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Total Tweets</th>
                                        <th>Total Likes</th>
                                        <th>Total Followers</th>
                                        <th>Total Followings</th>
                                        <th>Join Date</th>
                                    </thead>
                                    <tbody>
                                        <tr v-if="participantInfo" v-for="(item, index) in participantInfo" :key="index">
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.email }}</td>
                                            <td>{{ item.tweets_count }}</td>
                                            <td>{{ item.likes_count }}</td>
                                            <td>{{ item.followers_count }}</td>
                                            <td>{{ item.following_count }}</td>
                                            <td>{{ item.join_date }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {Link, router} from '@inertiajs/vue3'
    import {onMounted, ref} from "vue";

    const userInfo = ref('');
    const totalInfo = ref('');
    const participantInfo = ref('');

    const accessToken = localStorage.getItem('accessToken');

    const deleteCookie = (name) => {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;';
    }

    const logout = () => {
        axios.defaults.headers.common = {'Authorization': `bearer ` + accessToken}

        axios.post('v1/logout')
            .then(res => {
                localStorage.removeItem('accessToken');

                deleteCookie('web_cookie');

                router.get('/');
            })
            .catch(err => {});
    };

    const profile = () => {
        axios({
            method: 'post',
            url: 'v1/profile',
            headers: { 'Authorization': 'Bearer ' + accessToken }
        })
        .then(res => {
            userInfo.value = res.data.data;
        });
    };

    const getTotalInfos = () => {
        axios({
            method: 'post',
            url: 'v1/total-info',
            headers: { 'Authorization': 'Bearer ' + accessToken }
        })
        .then(res => {
            totalInfo.value = res.data.data;
        });
    };

    const getParticipants = () => {
        axios({
            method: 'get',
            url: 'v1/participant-list',
            headers: { 'Authorization': 'Bearer ' + accessToken }
        })
        .then(res => {
            participantInfo.value = res.data.data;
        });
    };

    onMounted(() => {
        if (! accessToken) {
            router.get('/');
        }

        profile();
        getTotalInfos();
        getParticipants();
    });
</script>

