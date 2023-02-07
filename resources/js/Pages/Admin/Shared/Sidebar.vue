<template>
    <nav :class="`${!isSidebarOpen ? 'close' : ''}`">
        <div class="menu-items">
            <ul class="nav-links">
                <li>
                    <Link href="/">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Dashboard</span>
                    </Link>
                </li>
                <li>
                    <Link href="/company">
                        <i class="uil uil-building"></i>
                        <span class="link-name">Company Profile</span>
                    </Link>
                </li>
                <li>
                    <Link href="/users">
                        <i class="uil uil-users-alt"></i>
                        <span class="link-name">User Accounts</span>
                    </Link>
                </li>
                <li>
                    <Link href="/bankaccounts">
                        <i class="uil uil-credit-card"></i>
                        <span class="link-name">Bank Accounts</span>
                    </Link>
                </li>
                <li>
                    <Link href="/voucherapprovals">
                        <i class="uil uil-inbox"></i>
                        <span class="link-name">Voucher Requests</span>
                    </Link>
                </li>
            </ul>
            
            <ul class="logout-mode">
                <li>
                    <Link href="/logout" method="post">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import { HomeIcon, UsersIcon, CreditCardIcon, InboxInIcon, OfficeBuildingIcon } from "@heroicons/vue/outline";

export default {
    data() {
        return {
            isSidebarOpen : true,
        }
    },
    props: {
        isSidebarOpen: Boolean,
    },
    components: { 
        HomeIcon, 
        UsersIcon, 
        CreditCardIcon, 
        InboxInIcon, 
        OfficeBuildingIcon
    },
    methods: {
    }
};
</script>

<style scoped>

nav{
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    padding: 10px 14px;
    background-color: var(--panel-color);
    border-right: 1px solid var(--border-color);
    transition: var(--tran-05);
}
nav.close{
    width: 73px;
}
nav .logo-name{
    display: flex;
    align-items: center;
}
nav .logo-image{
    display: flex;
    justify-content: center;
    min-width: 45px;
}
nav .logo-image img{
    width: 40px;
    object-fit: cover;
    border-radius: 50%;
}

nav .logo-name .logo_name{
    font-size: 22px;
    font-weight: 600;
    color: var(--text-color);
    margin-left: 14px;
    transition: var(--tran-05);
}
nav.close .logo_name{
    opacity: 0;
    pointer-events: none;
}
nav .menu-items{
    margin-top: 40px;
    height: calc(100% - 90px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.menu-items li{
    list-style: none;
}
.menu-items li a{
    display: flex;
    align-items: center;
    height: 50px;
    text-decoration: none;
    position: relative;
    border-radius: 15px;
}
.nav-links li a:hover:before{
    content: "";
    position: absolute;
    left: -7px;
    height: 5px;
    width: 5px;
    border-radius: 50%;
    background-color: #b7b7b7;
}
body.dark li a:hover:before{
    background-color: var(--text-color);
}
.menu-items li a i{
    font-size: 24px;
    min-width: 45px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--black-light-color);
}

.menu-items li a .link-name{
    font-size: 18px;
    font-weight: 400;
    color: var(--black-light-color);    
}
nav.close li a .link-name{
    opacity: 0;
    pointer-events: none;
}

.nav-links li a:hover {
    background-color: var(--color-primary);
}

.nav-links li a:hover i,
.nav-links li a:hover .link-name{
    color: #ffffff;
}
body.dark .nav-links li a:hover i,
body.dark .nav-links li a:hover .link-name{
    color: var(--text-color);
}
.menu-items .logout-mode{
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
}

nav.close .nav-links li a:hover {
    
}

@media (max-width: 1000px) {
    nav{
        width: 73px;
    }
    nav.close{
        width: 250px;
    }
    nav .logo_name{
        opacity: 0;
        pointer-events: none;
    }
    nav.close .logo_name{
        opacity: 1;
        pointer-events: auto;
    }
    nav li a .link-name{
        opacity: 0;
        pointer-events: none;
    }
    nav.close li a .link-name{
        opacity: 1;
        pointer-events: auto;
    }
}

@media (max-width: 400px) {
    nav{
        width: 0px;
    }
    nav.close{
        width: 73px;
    }
    nav .logo_name{
        opacity: 0;
        pointer-events: none;
    }
    nav.close .logo_name{
        opacity: 0;
        pointer-events: none;
    }
    nav li a .link-name{
        opacity: 0;
        pointer-events: none;
    }
    nav.close li a .link-name{
        opacity: 0;
        pointer-events: none;
    }
}
</style>