#include <iostream>
#include<cstdio>
using namespace std;
int a[101][101],i,j,n;
void royfloyd()
{
    int i,j,k;
    for(k=1; k<=n; k++)
        for(i=1; i<=n; i++)
            for(j=1; j<=n; j++)
                if ( a[i][k] && a[k][j] && (a[i][j] > a[i][k] + a[k][j] || !a[i][j]) && i!=j )
                    a[i][j]=a[i][k]+a[k][j];
}
int main()
{
    freopen("royfloyd.in","r",stdin);
    freopen("royfloyd.out","w",stdout);
    scanf("%d",&n);
    for (i = 1; i <= n; i++)
        for (j = 1; j <= n; j++) scanf("%d",&a[i][j]);
    royfloyd();
    for (i = 1; i <= n; i++)
    {
        for (j = 1; j <= n; j++) printf("%d ",a[i][j]);
        printf("\n");
    }


}
