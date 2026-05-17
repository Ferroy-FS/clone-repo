const users = [
{username:"admin",password:"123",role:"admin"},
{username:"trainer1",password:"123",role:"trainer"},
{username:"member1",password:"123",role:"member"}
];

const loginSection = document.getElementById("loginSection");
const dashboardSection = document.getElementById("dashboardSection");
const loginForm = document.getElementById("loginForm");
const errorMessage = document.getElementById("errorMessage");
const welcomeText = document.getElementById("welcomeText");
const logoutBtn = document.getElementById("logoutBtn");

const menuAttendance = document.getElementById("menuAttendance");
const menuPayment = document.getElementById("menuPayment");
const menuTrainer = document.getElementById("menuTrainer");
const menuRental = document.getElementById("menuRental");
const menuIncome = document.getElementById("menuIncome");

window.onload = function(){
    checkSession();
    loadAllData();
};

function startSession(user){
    loginSection.style.display="none";
    dashboardSection.style.display="block";
    welcomeText.innerText=
    "Welcome "+user.username+" ("+user.role+")";
    resetMenu();
    applyRole(user.role);
}
loginForm.addEventListener("submit", function(e){

    e.preventDefault();

    let u = usernameInput.value.trim();
    let p = passwordInput.value.trim();

    let found = users.find(x=>x.username===u && x.password===p);

    if(found){
        localStorage.setItem("fitnessUser", JSON.stringify(found));
        startSession(found);
        errorMessage.innerText="";
    }else{
        errorMessage.innerText="Invalid Username or Password";
    }

});
function startSession(user){
    loginSection.style.display="none";
    dashboardSection.style.display="block";
    welcomeText.innerText=
    "Welcome "+user.username+" ("+user.role+")";
    resetMenu();
    applyRole(user.role);
}

logoutBtn.onclick=function(){
    localStorage.removeItem("fitnessUser");
    dashboardSection.style.display="none";
    loginSection.style.display="flex";
};

function resetMenu(){
    menuAttendance.style.display="block";
    menuPayment.style.display="block";
    menuTrainer.style.display="block";
    menuRental.style.display="block";
    menuIncome.style.display="block";
}

function applyRole(role){

    if(role==="trainer"){
        menuPayment.style.display="none";
        menuIncome.style.display="none";
    }

    if(role==="member"){
        menuAttendance.style.display="none";
        menuPayment.style.display="none";
        menuIncome.style.display="none";
        menuTrainer.style.display="none";
    }

    showSection("attendanceSection");
}
function showSection(id){
    document.querySelectorAll(".contentSection")
    .forEach(s=>s.style.display="none");

    document.getElementById(id).style.display="block";
}

function getData(key){
    return JSON.parse(localStorage.getItem(key)) || [];
}

function saveData(key,data){
    localStorage.setItem(key, JSON.stringify(data));
}


document.getElementById("saveAtt").onclick=function(){

    const name = attName.value;
    const date = attDate.value;
    const status = attStatus.value;

    if(!name || !date) return;

    let data = getData("attendance");

    data.push({name,date,status});

    saveData("attendance",data);

    renderAttendance();
};

function renderAttendance(){

    let table = document.getElementById("attTable");
    table.innerHTML="";

    let data = getData("attendance");

    data.forEach(item=>{
        table.innerHTML+=
        `<tr>
        <td>${item.name}</td>
        <td>${item.date}</td>
        <td>${item.status}</td>
        </tr>`;
    });
}

document.getElementById("savePay").onclick=function(){

    const name = payName.value;
    const amount = payAmount.value;
    const date = payDate.value;

    if(!name || !amount) return;

    let data = getData("payment");

    data.push({name,amount,date});

    saveData("payment",data);

    renderPayment();
};

function renderPayment(){

    let table = document.getElementById("payTable");
    table.innerHTML="";

    let data = getData("payment");

    data.forEach(item=>{
        table.innerHTML+=
        `<tr>
        <td>${item.name}</td>
        <td>${item.amount}</td>
        <td>${item.date}</td>
        </tr>`;
    });
}

document.getElementById("saveTr").onclick=function(){

    const name = trName.value;
    const date = trDate.value;
    const status = trStatus.value;

    if(!name || !date) return;

    let data = getData("trainer");

    data.push({name,date,status});

    saveData("trainer",data);

    renderTrainer();
};

function renderTrainer(){

    let table = document.getElementById("trTable");
    table.innerHTML="";

    let data = getData("trainer");

    data.forEach(item=>{
        table.innerHTML+=
        `<tr>
        <td>${item.name}</td>
        <td>${item.date}</td>
        <td>${item.status}</td>
        </tr>`;
    });
}


document.getElementById("saveRent").onclick=function(){

    const member = rentMember.value;
    const trainer = rentTrainer.value;
    const session = rentSession.value;
    const total = rentTotal.value;

    if(!member || !trainer) return;

    let data = getData("rental");

    data.push({member,trainer,session,total});

    saveData("rental",data);

    renderRental();
};

function renderRental(){

    let table = document.getElementById("rentTable");
    table.innerHTML="";

    let data = getData("rental");

    data.forEach(item=>{
        table.innerHTML+=
        `<tr>
        <td>${item.member}</td>
        <td>${item.trainer}</td>
        <td>${item.session}</td>
        <td>${item.total}</td>
        </tr>`;
    });
}

document.getElementById("calcIncome").onclick=function(){

    let data = getData("rental");
    let total = 0;

    data.forEach(item=>{
        total += parseInt(item.total || 0);
    });

    document.getElementById("totalIncome").innerText = total;
};


function loadAllData(){
    renderAttendance();
    renderPayment();
    renderTrainer();
    renderRental();
}