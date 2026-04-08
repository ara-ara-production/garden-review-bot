import{a as x,u as h,r as j,j as e}from"./app-CbBDZ2-1.js";import{H as g}from"./Head-C5rbOLc6.js";import{N as b}from"./NavBar-GBX9edZl.js";import{C as _,R as f,a as l,B as v}from"./Alert-CqTlS43w.js";import{F as w,a as C}from"./Label-BbD99JWr.js";import{a as n,R as y}from"./RowFormGroupSelect-DGNj5Bbf.js";import{R as N}from"./RowFormGroupWithPrefix-zzPAF2E4.js";import"./Progress-BkDbzNHp.js";import"./FormFeedback-_7wxNjiy.js";import"./Input-BEp1MAUa.js";const H=({values:t,roles:i})=>{const{data:a,setData:s,put:p,processing:u,errors:o}=x({name:t.name??"",telegram_username:t.telegram_username??"",vk_user_id:t.vk_user_id??"",email:t.email??"",password:"",password_confirmation:"",role:t.role??"NullRole"}),{routes:m}=h().props,[R,d]=j.useState(!1),c=r=>{r.preventDefault(),p(`/${m.backendprefix}/${m.user}/${t.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(g,{title:"Обновление пользователя"}),e.jsx(b,{}),e.jsxs(_,{children:[e.jsx(f,{children:e.jsx(l,{children:e.jsx("h2",{children:"Обновление пользователя"})})}),e.jsxs(w,{className:"row",onSubmit:c,children:[e.jsxs(l,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(n,{label:"Имя *",inputType:"text",value:a.name,onChange:r=>s("name",r.target.value),error:o.name}),e.jsx(N,{label:"Телеграм никнейм",inputType:"text",value:a.telegram_username,onChange:r=>s("telegram_username",r.target.value),error:o.telegram_username,formText:"Необходимо заполнить, того, чтоб пользователь мог использовать бота"}),e.jsx(n,{label:"VK user id",inputType:"text",value:a.vk_user_id,onChange:r=>s("vk_user_id",r.target.value),error:o.vk_user_id}),e.jsx(y,{label:"Роль",options:i,value:a.role,onChange:r=>s("role",r.target.value),error:o.role})]}),e.jsx("h6",{className:"pl-0 text-muted",children:"При заполнении пользователь сможет входить в админ панель:"}),e.jsxs(l,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(n,{label:"Почта",inputType:"email",value:a.email,onChange:r=>s("email",r.target.value),error:o.email}),e.jsx(n,{label:"Пароль",inputType:"password",value:a.password,onChange:r=>s("password",r.target.value),error:o.password}),e.jsx(n,{label:"Повтор пароля",inputType:"password",value:a.password_confirmation,onChange:r=>s("password_confirmation",r.target.value),error:o.password_confirmation})]}),e.jsx(l,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(C,{children:e.jsx(v,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:u,onClick:()=>d(!1),children:"Обновить"})})})]})]})]})};export{H as default};
