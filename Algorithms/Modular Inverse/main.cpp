
#include <iostream>
#include <fstream>
using namespace std;

ifstream f("inversmodular.in");
ofstream g("inversmodular.out");

long long a,n,y0,y1,y,aux,r,c;

long long invers_modular(long long a,long long MOD)
{
    y0=0;y1=1;
    aux=MOD;
    while(a)
    {
        r=MOD%a;
        c=MOD/a;
        MOD=a;
        a=r;
        y=y0-c*y1;
        y0=y1;
        y1=y;
    }
    while(y0<0)
        y0+=aux;
    return y0;
}

int main()
{
    f>>a>>n;
    g<<invers_modular(a,n)%n;
}
