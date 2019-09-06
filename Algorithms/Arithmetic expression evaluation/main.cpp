#include <iostream>
#include<fstream>
using namespace std;
ifstream f("evaluare.in");
ofstream g("evaluare.out");
char s[100010];
int p;
long termen();
long factor();

long eval()
{
    long r=termen();
    while(s[p]=='+' || s[p]=='-')
    {
        switch(s[p])
        {
        case '+':
            ++p;
            r+=termen();
            break;
        case '-':
            ++p;
            r-=termen();
            break;
        }
    }
    return r;
}
long termen()
{
    long r=factor();
    while(s[p]=='*' || s[p]=='/')
    {
        switch(s[p])
        {
        case '*':
            ++p;
            r*=factor();
            break;
        case '/':
            ++p;
            r/=factor();
            break;
        }
    }
    return r;
}
long factor()
{
    long r=0;
    if(s[p]=='(')
    {
        ++p;
        r=eval();
        ++p;
    }
    else while(s[p]>='0' && s[p]<='9')
    {
        r=r*10+(s[p]-'0');
        ++p;
    }
    return r;
}
int main()
{
    f>>s;
    g<<eval();
}
