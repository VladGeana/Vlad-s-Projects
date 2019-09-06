#include <iostream>
#include<fstream>
using namespace std;
ifstream f("cmlsc.in");
ofstream g("cmlsc.out");
int n,m,a[1024],b[1024],w[1024][1024],i,j,v[1024],vf[1024],i1,j1,k=0;
int main()
{
    f>>n>>m;for(i=1;i<=n;i++) f>>a[i];
    for(i=1;i<=m;i++) f>>b[i];
    for(i=1;i<=n;i++)
        for(j=1;j<=m;j++)
    {
        if(a[i]==b[j]) w[i][j]=1+w[i-1][j-1];
        else w[i][j]=max(w[i-1][j],w[i][j-1]);
    }

    i=n;j=m;k=0;
    while(i) //build answer
    {
        if(a[i]==b[j])
        {
            k++;
            v[k]=a[i];
            i--;j--;
        }
        else if(w[i-1][j]<w[i][j-1])
            j--;
        else i--;
    }
    g<<w[n][m]<<'\n';
    for(i=w[n][m];i>=1;i--) g<<v[i]<<' ';

}
